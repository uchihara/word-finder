require 'set'
require_relative 'matrix'

class StringWalker
  def initialize matrix
    @matrix = matrix
  end

  def scan length, x=0, y=0
    walk x, y, [], length, Set.new
  end

  private
  def walk x, y, footprints, length, found_words
    raise "invalid length: #{length}" if length <= 0
    raise "invalid footprints: #{footprints}" if footprints.length > length
    raise "invalid pos, x: #{x}, y: #{y}" unless @matrix.exists?(x, y)
    raise "invalid mark, x: #{x}, y: #{y}" if @matrix.at(x, y).marked?

    @matrix.at(x, y).mark!
    footprints.push @matrix.at(x, y).char
    if footprints.length == length
      found_words << footprints.join
    else
      [-1, 0, 1].each do |dx|
        [-1, 0, 1].each do |dy|
          if @matrix.exists?(x+dx, y+dy) && !@matrix.at(x+dx, y+dy).marked?
            walk x+dx, y+dy, footprints, length, found_words
          end
        end
      end
    end
    footprints.pop
    @matrix.at(x, y).unmark!
    found_words
  end
end
